export default function Num(props: { value: number; money?: true }) {
  return (
    <span aria-label={props.money ? `$${props.value}` : undefined}>
      {props.value.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })}
    </span>
  );
}
