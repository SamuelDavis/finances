export type Targeted<
  Ev extends Event,
  El extends Element = Ev extends InputEvent
    ? HTMLInputElement
    : Ev extends SubmitEvent
      ? HTMLFormElement
      : HTMLElement,
> = Ev & { currentTarget: El; target: Element };

export type Source = {
  name: string;
  amount: number;
  interval: number;
};

export const WeeksPerYear = 52.143 as const;
