import { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import {Inertia} from "@inertiajs/inertia";

export default function CounterForm({ item = null}) {
    const [type, setType] = useState(item ? item.type : 'pregnancy');
    const [counterName, setCounterName] = useState(item ? item.name : '');
    const [kidName, setKidName] = useState(item?.kid?.name || '');
    const [gender, setGender] = useState(item?.kid?.gender || '');
    const [birthday, setBirthday] = useState(item?.kid?.birthday || '');
    const [dueDate, setDueDate] = useState(item?.pregnancy?.termin_date ||'');
    const [kids, setKids] = useState(item?.pregnancy?.fetuses||[]);
    const [successMessage, setSuccessMessage] = useState('');
    const [errors, setErrors] = useState({});

    const today = new Date();
    const maxDate = new Date(today);
    maxDate.setDate(today.getDate() + 280); // 280 days from today

    const formattedMaxDate = maxDate.toISOString().split('T')[0]; // Format date as YYYY-MM-DD
    const formattedToday = today.toISOString().split('T')[0]; // Format today as YYYY-MM-DD

    const handleAddKid = () => {
        setKids([...kids, { gender: '' }]); // Only gender field for new kid
    };

    const handleKidChange = (index, value) => {
        const updatedKids = [...kids];
        updatedKids[index].gender = value; // Update only gender for each kid
        setKids(updatedKids);
    };

    const handleRemoveKid = (index) => {
        const updatedKids = kids.filter((_, i) => i !== index);
        setKids(updatedKids);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        if (type === 'pregnancy' && kids.length === 0) {
            alert('At least one kid is required for a pregnancy.');
            return; // Prevent form submission
        }

        const formData = {
            counterName,
            type,
            kidName,
            gender,
            birthday,
            dueDate,
            kids,
        };

        try {
            let response;
            if (item) {
                // If item exists, update it
                const route =
                    type === 'pregnancy'
                        ? `/updatePregnancy/${item.id}`
                        : `/updateKid/${item.id}`;
                response = await axios.put(route, formData);
                setSuccessMessage('Counter updated successfully!');

            } else {
                // Create a new counter
                const route = type === 'pregnancy' ? '/storePregnancy' : '/storeKid';
                response = await axios.post(route, formData);

                setSuccessMessage('Counter created successfully!');
                setCounterName('');
                setType('');
                setKidName('');
                setGender('');
                setBirthday('');
                setDueDate('');
                setKids([]);
            }



            setErrors({});
            setTimeout(() => setSuccessMessage(''), 3000);
        } catch (error) {
            if (error.response && error.response.data.errors) {
                setErrors(error.response.data.errors);
            } else {
                console.error('Error submitting the form:', error);
            }
            setSuccessMessage('');
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <h3 className="text-xl font-semibold leading-tight text-gray-800">
                    {item ? 'Update Counter' : 'Create New Counter'}
                </h3>
            }
        >
            <Head title={item ? 'Update Counter' : 'Create New Counter'} />

            <div className="container mx-auto p-6">
                <div className="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    {successMessage && <div className="bg-blue-500 text-white p-4 mb-4 rounded">{successMessage}</div>}
                    {Object.keys(errors).length > 0 && (
                        <div className="bg-red-500 text-white p-4 mb-4 rounded">
                            <ul>
                                {Object.entries(errors).map(([field, messages]) => (
                                    <li key={field}>
                                        {messages.map((message, idx) => (
                                            <span key={idx}>{message}</span>
                                        ))}
                                    </li>
                                ))}
                            </ul>
                        </div>
                    )}

                    <form onSubmit={handleSubmit}>
                        <div className="mb-4">
                            <label htmlFor="counterName" className="block text-gray-700 font-bold mb-2">
                                Counter Name
                            </label>
                            <input
                                type="text"
                                id="counterName"
                                name="counterName"
                                value={counterName}
                                onChange={(e) => setCounterName(e.target.value)}
                                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required
                            />
                        </div>

                        <div className="mb-4">
                            <label htmlFor="type" className="block text-gray-700 font-bold mb-2">
                                Type
                            </label>
                            <select
                                id="type"
                                name="type"
                                value={type}
                                onChange={(e) => setType(e.target.value)}
                                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required
                            >
                                <option value="kid">Kid</option>
                                <option value="pregnancy">Pregnancy</option>
                            </select>
                        </div>

                        {type === 'kid' && (
                            <>
                                <div className="mb-4">
                                    <label htmlFor="kidName" className="block text-gray-700 font-bold mb-2">
                                        Kid Name
                                    </label>
                                    <input
                                        type="text"
                                        id="kidName"
                                        name="kidName"
                                        value={kidName}
                                        onChange={(e) => setKidName(e.target.value)}
                                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        required
                                    />
                                </div>
                                <div className="mb-4">
                                    <label htmlFor="gender" className="block text-gray-700 font-bold mb-2">
                                        Gender
                                    </label>
                                    <select
                                        id="gender"
                                        name="gender"
                                        value={gender}
                                        onChange={(e) => setGender(e.target.value)}
                                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        required
                                    >
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div className="mb-4">
                                    <label htmlFor="birthday" className="block text-gray-700 font-bold mb-2">
                                        Birthday
                                    </label>
                                    <input
                                        type="date"
                                        id="birthday"
                                        name="birthday"
                                        value={birthday}
                                        onChange={(e) => setBirthday(e.target.value)}
                                        max={new Date().toISOString().split('T')[0]}
                                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        required
                                    />
                                </div>
                            </>
                        )}

                        {type === 'pregnancy' && (
                            <>
                                <div className="mb-4">
                                    <label htmlFor="dueDate" className="block text-gray-700 font-bold mb-2">
                                        Due Date
                                    </label>
                                    <input
                                        type="date"
                                        id="dueDate"
                                        name="dueDate"
                                        value={dueDate}
                                        onChange={(e) => setDueDate(e.target.value)}
                                        min={formattedToday}  // Prevent past dates
                                        max={formattedMaxDate} // Limit to 280 days from today
                                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        required
                                    />
                                </div>

                                <div className="mb-4">
                                    <label className="block text-gray-700 font-bold mb-2">Kids</label>
                                    {kids.map((kid, index) => (
                                        <div key={index} className="flex gap-4 mb-2">
                                            <select
                                                value={kid.gender}
                                                onChange={(e) => handleKidChange(index, e.target.value)}
                                                className="shadow appearance-none border rounded py-2 px-10 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                required
                                            >
                                                <option value="">Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="unknown">Unknown</option>
                                            </select>
                                            <button
                                                type="button"
                                                onClick={() => handleRemoveKid(index)}
                                                className="bg-red-500 text-white rounded px-2 py-1"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    ))}
                                    <button
                                        type="button"
                                        onClick={handleAddKid}
                                        className="bg-blue-500 text-white rounded px-4 py-2 mt-2"
                                    >
                                        Add Kid
                                    </button>
                                    {/* Feedback message */}
                                    {type === 'pregnancy' && kids.length === 0 && (
                                        <p className="text-red-500 mt-2">You must add at least one kid for a
                                            pregnancy.</p>
                                    )}
                                </div>
                            </>
                        )}

                        <div className="flex items-center justify-between">
                            <button
                                type="submit"
                                className={`font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ${
                                    item
                                        ? 'bg-yellow-500 hover:bg-yellow-700 text-white'
                                        : 'bg-blue-500 hover:bg-blue-700 text-white'
                                }`}
                            >
                                {item ? 'Update Counter' : 'Create Counter'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
