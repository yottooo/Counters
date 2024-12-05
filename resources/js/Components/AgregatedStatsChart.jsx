import React from "react";
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from "recharts";

const AggregatedStatsChart = ({ stats }) => {
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
            <h2>Aggregated User Stats</h2>
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
