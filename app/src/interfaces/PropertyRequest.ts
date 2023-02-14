import type { Lodger } from '@/interfaces/Lodger';
import type { Availaibility } from './Availaibility';
import type { Property } from './Property';

export interface PropertyRequest {
    id: number, 
    uuid: string,
    lodger: Lodger,
    property: Property,
    state: string,
    availaibilities: Availaibility[]
}
