import React, { useEffect, useState } from "react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Legend,
    ResponsiveContainer, Cell
} from "recharts";
import axios from "axios";

const KidsByAgeStats = () => {
    const [stats, setStats] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        // Fetch kids by age stats from the backend API
        axios.get('/stats/kidsByAge')
            .then(response => {
                setStats(response.data);
                setLoading(false);
            })
            .catch(error => {
                setError('Failed to load kids stats');
                setLoading(false);
            });
    }, []);

    if (loading) {
        return <div>Loading...</div>;
    }

    if (error) {
        return <div>{error}</div>;
    }

    // Assign specific colors for each age group
    const COLORS = {
        "Below 1": "#FF6347",   // Tomato
        "1-6": "#FFD700",      // Gold
        "7-18": "#32CD32",     // LimeGreen
    };

    // Map stats to include fill color for each group
    const chartData = stats.map(stat => ({
        ...stat,
        fill: COLORS[stat.age_group] || "#8884d8" // Default color if age group is missing
    }));

    return (
        <div>
            <ResponsiveContainer width="100%" height={400}>
                <BarChart data={chartData}>
                    <CartesianGrid strokeDasharray="3 3" />
                    <XAxis dataKey="age_group" />
                    <YAxis />
                    <Tooltip />
                    <Legend />
                    <Bar
                        dataKey="count"
                        name="Kids Count"
                        fill="#8884d8"
                        label={{ position: "top" }}
                        barSize={100}
                    >
                        {chartData.map((entry, index) => (
                            <Cell key={`cell-${index}`} fill={entry.fill} />
                        ))}
                    </Bar>
                </BarChart>
            </ResponsiveContainer>
        </div>
    );
};

export default KidsByAgeStats;
