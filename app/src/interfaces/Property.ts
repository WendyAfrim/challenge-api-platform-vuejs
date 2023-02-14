import type { PropertyRequest } from '@/interfaces/PropertyRequest';

export interface Property {
    id: number,
    title: string,
    address: string,
    zipcode: string,
    price : number,
    state : string,
    requests: PropertyRequest
}
