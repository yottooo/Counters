import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Statistics() {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Statistics
                </h2>
            }
        >
            <Head title="Statistics" />


        </AuthenticatedLayout>
    );
}
