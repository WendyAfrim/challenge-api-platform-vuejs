import type { Lodger } from "./Lodger";
import type { Property } from "./Property";

export interface Availaibility 
{
    property: Property,
    lodger: Lodger,
    slot: object
}
