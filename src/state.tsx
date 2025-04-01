import { createEffect, createRoot, createSignal } from "solid-js";
import { createStore } from "solid-js/store";
import { type Source, WeeksPerYear } from "./types";

export const sourceStore = createRoot(() => {
  const [get, set] = createPersistentSignal<Source[]>({
    key: "sources",
    reviver: [],
  });

  const getWeeklyTotal = () =>
    get().reduce(
      (acc, source) =>
        acc + (source.interval === 0 ? 0 : source.amount / source.interval),
      0,
    );

  return { get, set, getWeeklyTotal };
});

export const savingsStore = createRoot(() => {
  const [get, set] = createPersistentSignal({
    key: "savings",
    reviver: 0,
    equals: false,
  });

  return { get, set };
});

export const worryAtStore = createRoot(() => {
  const [get, set] = createPersistentSignal({
    key: "worryAt",
    reviver: 0,
    equals: false,
  });

  return { get, set };
});

export const derivedStore = createRoot(() => {
  const [derived, set] = createStore(deriveStore());
  createEffect(() => set(deriveStore()));

  return derived;
});

function deriveStore() {
  const weeklyTotal = sourceStore.getWeeklyTotal();
  const savings = savingsStore.get();
  const worryAt = worryAtStore.get();

  const annualTotal = weeklyTotal * WeeksPerYear;
  const untilWorryIn =
    annualTotal >= 0
      ? Number.POSITIVE_INFINITY
      : (savings - worryAt) / Math.abs(annualTotal);
  const untilSavingsZero =
    annualTotal >= 0
      ? Number.POSITIVE_INFINITY
      : savings / Math.abs(annualTotal);
  const untilWorryZero =
    annualTotal >= 0
      ? Number.POSITIVE_INFINITY
      : worryAt / Math.abs(annualTotal);

  return {
    annualTotal,
    untilWorryIn,
    untilSavingsZero,
    untilWorryZero,
  };
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
