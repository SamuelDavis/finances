import { Accessor, For, Show, createEffect, createSignal } from "solid-js";

export type Targeted<
  Ev extends Event,
  El extends Element = Ev extends InputEvent
    ? HTMLInputElement
    : Ev extends SubmitEvent
      ? HTMLFormElement
      : HTMLElement,
> = Ev & { currentTarget: El; target: Element };

type Source = {
  name: string;
  amount: number;
  interval: number;
};

const WeeksPerYear = 52.143 as const;

export default function App() {
  const [getSources, setSources] = createPersistentSignal<Source[]>({
    key: "sources",
    reviver: [],
  });
  const [getSavings, setSavings] = createPersistentSignal({
    key: "savings",
    reviver: 0,
    equals: false,
  });
  const [getWorryAt, setWorryAt] = createPersistentSignal({
    key: "worryAt",
    reviver: 0,
    equals: false,
  });

  const getAnnualTotal = (): number => {
    const sources = getSources();
    return sources.reduce((acc, source) => {
      return source.interval === 0
        ? acc
        : acc + source.amount * (WeeksPerYear / source.interval);
    }, 0);
  };
  const getWorryIn = (): undefined | number => {
    const annualTotal = getAnnualTotal();
    const savings = getSavings();
    const worryAt = getWorryAt();
    const worryMin = savings - worryAt;

    if (annualTotal > 0) return undefined;
    if (worryMin + annualTotal < worryAt) return -1;
    return worryMin / Math.abs(annualTotal);
  };
  const getNoSavingsIn = (): undefined | number => {
    const savings = getSavings();
    const worryAt = getWorryAt();
    const annualTotal = getAnnualTotal();
    const worryMin = Math.min(savings, worryAt);

    if (annualTotal > 0) return undefined;
    return worryMin / Math.abs(annualTotal);
  };

  function onAddSource(event: Targeted<SubmitEvent>) {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    const name = data.get("name")?.toString();
    if (!name) throw new TypeError();
    const amount = Number(data.get("amount")?.toString());
    if (Number.isNaN(amount)) throw new TypeError();
    const interval = Number(data.get("interval")?.toString());
    if (Number.isNaN(interval)) throw new TypeError();

    setSources((sources) => [
      ...sources.filter((source) => source.name !== name),
      { name, amount, interval },
    ]);

    event.currentTarget.reset();
    event.currentTarget.querySelector("input")?.focus();
  }

  function onUpdateSavings(event: Targeted<InputEvent>) {
    setSavings(event.currentTarget.valueAsNumber || 0);
  }
  function onUpdateWorryAt(event: Targeted<InputEvent>) {
    setWorryAt(event.currentTarget.valueAsNumber || 0);
  }

  return (
    <article>
      <header>
        <h1>App</h1>
      </header>
      <div aria-label="group">
        <section>
          <table>
            <thead>
              <tr>
                <th>Name</th>
                <th>Amount</th>
                <th>Interval</th>
                <th>Annual</th>
              </tr>
            </thead>
            <tbody>
              <For each={getSources()}>
                {(source) => (
                  <tr>
                    <td>{source.name}</td>
                    <td>
                      <span>$</span>
                      <Num value={source.amount} money />
                    </td>
                    <td>{source.interval.toLocaleString()}</td>
                    <td>
                      <Num
                        value={(source.amount * source.interval) / WeeksPerYear}
                        money
                      />
                    </td>
                  </tr>
                )}
              </For>
            </tbody>
          </table>
          <form onSubmit={onAddSource}>
            <label>
              <span>Name</span>
              <input type="text" name="name" id="name" required />
            </label>
            <label>
              <span>Amount</span>
              <input
                type="number"
                name="amount"
                id="amount"
                step={1}
                required
              />
            </label>
            <fieldset aria-label="group">
              <legend>Every N Intervals</legend>
              <label>
                <span>N...</span>
                <input
                  type="number"
                  name="interval"
                  id="interval"
                  min="0"
                  required
                />
              </label>
              <label>
                <span>Intervals</span>
                <select name="interval" id="interval" required>
                  <option value="week">Weeks</option>
                </select>
              </label>
            </fieldset>
            <input type="submit" value="Add Source" />
          </form>
        </section>
        <section>
          <fieldset aria-label="group">
            <label>
              <span>Current savings</span>
              <input
                type="number"
                name="savings"
                id="savings"
                onInput={onUpdateSavings}
                value={getSavings()}
              />
            </label>
            <label>
              <span>Worry at...</span>
              <input
                type="number"
                name="worry"
                id="worry"
                onInput={onUpdateWorryAt}
                value={getWorryAt()}
              />
            </label>
          </fieldset>
          <table>
            <tbody>
              <tr>
                <td>Total</td>
                <td>
                  <span>$</span>
                  <output>
                    <Num value={getAnnualTotal()} money />
                  </output>
                </td>
                <td>per Year</td>
              </tr>
              <tr>
                <td>Worry in</td>
                <td>
                  <output>
                    <Deadline value={getWorryIn()} />
                  </output>
                </td>
                <td>Years</td>
              </tr>
              <tr>
                <td>No savings in</td>
                <td>
                  <output>
                    <Deadline value={getNoSavingsIn()} />
                  </output>
                </td>
                <td>Years</td>
              </tr>
            </tbody>
          </table>
        </section>
      </div>
    </article>
  );
}

function createPersistentSignal<T>(
  options: {
    key: string;
    reviver: T | ((value: string | null) => T);
  } & Parameters<typeof createSignal>[1],
): ReturnType<typeof createSignal<T>> {
  const { key, reviver, ...opts } = options;
  const stored = localStorage.getItem(key);
  const value =
    reviver instanceof Function
      ? reviver(stored)
      : (JSON.parse(stored ?? "null") ?? reviver);
  const signal = createSignal<T>(value, opts);
  createEffect(() => {
    const value = signal[0]();
    return localStorage.setItem(key, JSON.stringify(value));
  });
  return signal;
}

function Num(props: { value: number; money?: true }) {
  return (
    <span aria-label={props.money ? `${props.value} dollars` : undefined}>
      {props.value.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })}
    </span>
  );
}

function Deadline(props: { value: undefined | number }) {
  if (props.value === undefined) return "no";
  if (props.value === -1) return "now";
  return <Num value={props.value} />;
}
