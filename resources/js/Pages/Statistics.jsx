import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import AggregatedStatsChart from '../Components/AgregatedStatsChart.jsx'
import { useEffect, useState } from "react";
import axios from "axios";

export default function Statistics() {
    const [stats, setStats] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        // Fetch aggregated statistics from the backend API
        axios.get('/stats')
            .then(response => {
                setStats(response.data);
                setLoading(false);
            })
            .catch(error => {
                setError('Failed to load stats');
                setLoading(false);
            });
    }, []);

    if (loading) {
        return <div>Loading...</div>;
    }

    if (error) {
        return <div>{error}</div>;
    }

    // Check if stats is not null and contains required properties
    if (!stats || !stats.users || !stats.pregnancies || !stats.children || !stats.averageChildren) {
        return <div>Invalid data</div>;
    }

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Statistics
                </h2>
            }
        >
            <Head title="Statistics" />
            <AggregatedStatsChart stats={stats} />
        </AuthenticatedLayout>
    );
}
