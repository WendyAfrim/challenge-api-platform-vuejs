import type { Lodger } from "./Lodger";
import type { Property } from "./Property";
import type { PropertyRequest } from "./PropertyRequest";
import type { Viewing } from "./Viewing";

export interface Availaibility 
{
    id: number,
    property?: Property,
    lodger?: Lodger,
    slot: string,
    state?: string,
    request: PropertyRequest,
    viewing?: Viewing
}
