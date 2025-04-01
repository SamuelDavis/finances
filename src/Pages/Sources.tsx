import { For } from "solid-js";
import Num from "../Num";
import { sourceStore } from "../state";
import { type Targeted, WeeksPerYear } from "../types";

export default function Sources() {
  function onAddSource(event: Targeted<SubmitEvent>) {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    const name = data.get("name")?.toString();
    if (!name) throw new TypeError();
    const amount = Number(data.get("amount")?.toString());
    if (Number.isNaN(amount)) throw new TypeError();
    const interval = Number(data.get("interval")?.toString());
    if (Number.isNaN(interval)) throw new TypeError();

    sourceStore.set((sources) => [
      ...sources.filter((source) => source.name !== name),
      { name, amount, interval },
    ]);

    event.currentTarget.reset();
    event.currentTarget.querySelector("input")?.focus();
  }

  return (
    <article>
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
          <For each={sourceStore.get()}>
            {(source) => (
              <tr>
                <td>{source.name}</td>
                <td>
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
        <fieldset aria-label="group">
          <legend>Source</legend>
          <label>
            <span>Name</span>
            <input type="text" name="name" id="name" required />
          </label>
          <label>
            <span>Amount</span>
            <input type="number" name="amount" id="amount" step={1} required />
          </label>
        </fieldset>
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
    </article>
  );
}
