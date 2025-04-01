import Num from "../Num";
import { derivedStore, savingsStore, worryAtStore } from "../state";
import type { Targeted } from "../types";

export default function Details() {
  function onUpdateSavings(event: Targeted<InputEvent>) {
    savingsStore.set(event.currentTarget.valueAsNumber || 0);
  }
  function onUpdateWorryAt(event: Targeted<InputEvent>) {
    worryAtStore.set(event.currentTarget.valueAsNumber || 0);
  }

  return (
    <article>
      <fieldset aria-label="group">
        <label>
          <span>Current savings</span>
          <input
            type="number"
            name="savings"
            id="savings"
            onInput={onUpdateSavings}
            value={savingsStore.get()}
          />
        </label>
        <label>
          <span>Worry at...</span>
          <input
            type="number"
            name="worry"
            id="worry"
            onInput={onUpdateWorryAt}
            value={worryAtStore.get()}
          />
        </label>
      </fieldset>
      <table>
        <tbody>
          <tr>
            <td>Total</td>
            <td>
              <output>
                <Num value={derivedStore.annualTotal} money />
              </output>
            </td>
            <td>per Year</td>
          </tr>
          <tr>
            <td>Worry in</td>
            <td>
              <output>
                <Deadline value={derivedStore.untilWorryIn} />
              </output>
            </td>
            <td>Years</td>
          </tr>
          <tr>
            <td>No savings in</td>
            <td>
              <output>
                <Deadline value={derivedStore.untilWorryZero} />
              </output>
            </td>
            <td>Years</td>
          </tr>
        </tbody>
      </table>
    </article>
  );
}

function Deadline(props: { value: undefined | number }) {
  if (props.value === undefined) return "no";
  if (props.value === -1) return "now";
  return <Num value={props.value} />;
}
