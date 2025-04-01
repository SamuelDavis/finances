import Details from "./Details";
import Sources from "./Sources";

export default function App() {
  return (
    <article aria-label="group">
      <section>
        <Sources />
      </section>
      <section>
        <Details />
      </section>
    </article>
  );
}
