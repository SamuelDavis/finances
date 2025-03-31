import { ParentProps } from "solid-js";

export default function Layout(props: ParentProps) {
  return (
    <main>
      <header>
        <h1>Finances</h1>
      </header>
      {props.children}
    </main>
  );
}
