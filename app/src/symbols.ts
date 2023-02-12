import type { InjectionKey } from 'vue';
import type { Ref } from 'vue';

export const StepCounterKey: InjectionKey<Ref<number>> = Symbol('STEP_COUNTER');
export const CurrentStepIndexKey: InjectionKey<Ref<number>> = Symbol('CURRENT_STEP_INDEX');