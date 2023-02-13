import type { Availaibility } from "./Availaibility";
import type { Lodger } from "./Lodger";
import type { User } from "./User";

export interface Viewing {
    agent: User,
    lodger: Lodger
    availaibility: Availaibility
}