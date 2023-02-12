import type { Lodger } from '@/interfaces/Lodger';

export interface Request {
    id: number, 
    uuid: string,
    lodger: Lodger,
    is_accepted: boolean
}
