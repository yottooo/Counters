import { useState } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import AggregatedStatsChart from "../Components/AgregatedStatsChart";
import PregnancyByMonthStats from "../Components/PregnancyByMonthStats.jsx";
import KidsByAgeStats from "../Components/KidsByAgeStats";

export default function Statistics() {
    const [activeChart, setActiveChart] = useState("aggregated");

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Statistics
                </h2>
            }
        >
            <Head title="Statistics" />
            <div className="container mx-auto p-4">
                {/* Chart Menu */}
                <div className="flex justify-center space-x-4 mb-6">
                    <button
                        onClick={() => setActiveChart("aggregated")}
                        className={`px-4 py-2 font-bold rounded ${
                            activeChart === "aggregated" ? "bg-blue-500 text-white" : "bg-gray-200"
                        }`}
                    >
                        Aggregated Stats
                    </button>
                    <button
                        onClick={() => setActiveChart("pregnancy")}
                        className={`px-4 py-2 font-bold rounded ${
                            activeChart === "pregnancy" ? "bg-blue-500 text-white" : "bg-gray-200"
                        }`}
                    >
                        Pregnancies by Month
                    </button>
                    <button
                        onClick={() => setActiveChart("kidsByAge")}
                        className={`px-4 py-2 font-bold rounded ${
                            activeChart === "kidsByAge" ? "bg-blue-500 text-white" : "bg-gray-200"
                        }`}
                    >
                        Kids by Age
                    </button>
                </div>

                {/* Conditional Rendering of Charts */}
                {activeChart === "aggregated" && <AggregatedStatsChart />}
                {activeChart === "pregnancy" && <PregnancyByMonthStats />}
                {activeChart === "kidsByAge" && <KidsByAgeStats />}
            </div>
        </AuthenticatedLayout>
    );
}
