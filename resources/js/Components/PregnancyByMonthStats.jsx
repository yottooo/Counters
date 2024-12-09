import React, { useEffect, useState } from "react";
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer, Cell } from "recharts";
import axios from "axios";

const PregnancyByMonthStats = () => {
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    // Custom colors for bars
    const barColors = [
        "#FF5733", "#FFBD33", "#75FF33", "#33FF57",
        "#33FFBD", "#3380FF", "#5733FF", "#BD33FF",
        "#FF33BD", "#FF3368", "#FF3333", "#FF8C33"
    ];

    useEffect(() => {
        axios.get("/stats/pregnancyByMonth")
            .then(response => {
                setData(response.data);
                setLoading(false);
            })
            .catch(err => {
                console.error(err);
                setError("Failed to load data");
                setLoading(false);
            });
    }, []);

    if (loading) return <div>Loading...</div>;
    if (error) return <div>{error}</div>;

    return (
        <div>
            <h2 className="text-lg font-semibold text-center mb-4">Pregnancies by Month</h2>
            <ResponsiveContainer width="100%" height={400}>
                <BarChart data={data} margin={{ top: 20, right: 30, left: 20, bottom: 10 }}>
                    <CartesianGrid strokeDasharray="3 3" />
                    <XAxis dataKey="month" />
                    <YAxis allowDecimals={false} />
                    <Tooltip />
                    <Legend />
                    <Bar
                        dataKey="count"
                        name="Pregnancies"
                        barSize={70} // Make bars wider
                        label={{ position: "top" }} // Show count on top
                    >
                        {data.map((entry, index) => (
                            <Cell key={`cell-${index}`} fill={barColors[index % barColors.length]} />
                        ))}
                    </Bar>
                </BarChart>
            </ResponsiveContainer>
        </div>
    );
};

export default PregnancyByMonthStats;
