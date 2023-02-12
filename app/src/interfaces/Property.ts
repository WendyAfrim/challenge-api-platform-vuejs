import type { Request } from '@/interfaces/Request';

export interface Property {
    id: number,
    title: string,
    address: string,
    zipcode: string,
    price : number,
    state : string,
    requests: Request
}
