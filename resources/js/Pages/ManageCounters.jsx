import { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, router} from '@inertiajs/react';

export default function ManageCounters({ counters }) {
    const [counterList, setCounterList] = useState(counters);

    const handleNewCounter = () => {
        // Navigate to the Edit Form page
        router.get('/CounterForm');
    };

    const handleEdit = (id) => {
        // Navigate to the Edit page for the specific counter
        router.get(`/CounterForm/${id}`);
    };

    const handleDelete = async (id) => {
        if (confirm('Are you sure you want to delete this counter?')) {
            try {
                // Send delete request to the backend
                await axios.delete(`/counter/${id}`);
                // After successful deletion, remove the counter from the list
                setCounterList(counterList.filter(counter => counter.id !== id));
            } catch (error) {
                console.error('Error deleting counter:', error);
            }
        }
    };

    return (
        <AuthenticatedLayout>
            <Head title="Manage Counters" />

            <div className="container mx-auto p-6">
                <div className="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <button
                        onClick={handleNewCounter}
                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4"
                    >
                        Create New Counter
                    </button>

                    {/* Table to display counters */}
                    <table className="min-w-full table-auto">
                        <thead>
                        <tr>
                            <th className="px-4 py-2 text-left">Counter ID</th>
                            <th className="px-4 py-2 text-left">Counter Name</th>
                            <th className="px-4 py-2 text-left">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        {counterList.map((counter) => (
                            <tr key={counter.id}>
                                <td className="border px-4 py-2">{counter.id}</td>
                                <td className="border px-4 py-2">{counter.name}</td>
                                <td className="border px-4 py-2">
                                    <button
                                        onClick={() => handleEdit(counter.id)}
                                        className="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded mr-2"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        onClick={() => handleDelete(counter.id)}
                                        className="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
