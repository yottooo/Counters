import React, {useEffect, useState} from "react";
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from "recharts";
import axios from "axios";

const AggregatedStatsChart = () => {
    const [stats, setStats] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        // Fetch aggregated statistics from the backend API
        axios.get('/stats/aggregated')
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
    const data = [
        {
            name: 'Users',
            count: stats.users,
            fill: '#800080',
        },
        {
            name: 'Pregnancies',
            count: stats.pregnancies,
            fill: '#0000FF',
        },
        {
            name: 'Children',
            count: stats.children,
            fill: '#FF0000',
        },
        {
            name: 'Average Children per Parent',
            count: stats.averageChildren,
            fill: '#00ff00',
        },
    ];

    return (
        <div>
            <ResponsiveContainer width="100%" height={400}>
                <BarChart data={data}>
                    <CartesianGrid strokeDasharray="3 3" />
                    <XAxis dataKey="name" />
                    <YAxis />
                    <Tooltip />
                    <Legend />
                    <Bar dataKey="count" fill="#8884d8" />
                </BarChart>
            </ResponsiveContainer>
        </div>
    );
};

export default AggregatedStatsChart;
