export interface Client {
    id: number
    first_name: string
    last_name: string
    email: string
    phone: string
    [key: string]: any
}

export interface ClientFormData {
    first_name: string;
    last_name: string;
    email: string;
    phone: string;
}