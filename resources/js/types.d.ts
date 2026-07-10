import '@inertiajs/core'

//type para definir el tipo de dato que tienen los props de inertia

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            flash: {
                success?: string
            }
        }
    }
}