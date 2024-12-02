import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.jsx";

export default function CounterForm({ }) {
    const something = () => {

    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Counter Form
                </h2>
            }
        >
            <Head title="Counter Form" />


        </AuthenticatedLayout>
    );
}
