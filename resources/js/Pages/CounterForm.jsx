import { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import axios from 'axios';

export default function CounterForm(counter = null ) {
    const [type, setType] = useState('kid'); // Default type is 'kid'
    const [counterName, setCounterName] = useState('');
    //Kid
    const [kidName, setKidName] = useState(''); // Renamed the variable to kidName
    const [gender, setGender] = useState('');
    const [birthday, setBirthday] = useState('');
    const [successMessage, setSuccessMessage] = useState('');  // State for success message
    const [errors, setErrors] = useState({});  // State for errors
    //Pregnancy TODO

    const handleSubmit = async (e) => {
        e.preventDefault();

        // Create the form data object
        const formData = {
            counterName,
            type,
            kidName,
            gender,
            birthday
        };

        try {
            // Send the form data to the store Kid route using axios
            const response = await axios.post('/storeKid', formData);

            // Display success message
            setSuccessMessage('Counter created successfully!');
            setErrors({});  // Reset errors if the request is successful

            // Reset the form fields
            setCounterName('');
            setType('kid');
            setKidName('');
            setGender('');
            setBirthday('');

            // Clear notifications after a short delay (optional)
            setTimeout(() => {
                setSuccessMessage('');
            }, 3000);  // Hide success message after 3 seconds
        } catch (error) {
            // Handle any errors that occurred during the request
            if (error.response && error.response.data.errors) {
                setErrors(error.response.data.errors);  // Set errors from backend
            } else {
                console.error('There was an error submitting the form:', error);
            }

            setSuccessMessage('');  // Reset success message if there's an error
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <h3 className="text-xl font-semibold leading-tight text-gray-800">
                    Create New Counter
                </h3>
            }
        >
            <Head title="Create New Counter" />

            <div className="container mx-auto p-6">
                <div className="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    {/* Success Notification */}
                    {successMessage && (
                        <div className="bg-blue-500 text-white p-4 mb-4 rounded">
                            {successMessage}
                        </div>
                    )}

                    {/* Error Notifications */}
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
                        {/* Counter Name Field */}
                        <div className="mb-4">
                            <label
                                htmlFor="counterName"
                                className="block text-gray-700 font-bold mb-2"
                            >
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
                            {errors.counterName && (
                                <span className="text-red-500 text-sm">{errors.counterName[0]}</span>
                            )}
                        </div>

                        {/* Type Field */}
                        <div className="mb-4">
                            <label
                                htmlFor="type"
                                className="block text-gray-700 font-bold mb-2"
                            >
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
                            </select>
                        </div>

                        {/* Kid Name Field (only for 'kid' type) */}
                        {type === 'kid' && (
                            <div className="mb-4">
                                <label
                                    htmlFor="kidName"
                                    className="block text-gray-700 font-bold mb-2"
                                >
                                    Kid Name
                                </label>
                                <input
                                    id="kidName"
                                    name="kidName"
                                    type="text"
                                    value={kidName}
                                    onChange={(e) => setKidName(e.target.value)}
                                    className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required
                                />
                                {errors.kidName && (
                                    <span className="text-red-500 text-sm">{errors.kidName[0]}</span>
                                )}
                            </div>
                        )}

                        {/* Gender Field (only for 'kid' type) */}
                        {type === 'kid' && (
                            <div className="mb-4">
                                <label
                                    htmlFor="gender"
                                    className="block text-gray-700 font-bold mb-2"
                                >
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
                                {errors.gender && (
                                    <span className="text-red-500 text-sm">{errors.gender[0]}</span>
                                )}
                            </div>
                        )}

                        {/* Birthday Field (only for 'kid' type) */}
                        {type === 'kid' && (
                            <div className="mb-4">
                                <label
                                    htmlFor="birthday"
                                    className="block text-gray-700 font-bold mb-2"
                                >
                                    Birthday
                                </label>
                                <input
                                    type="date"
                                    id="birthday"
                                    name="birthday"
                                    value={birthday}
                                    onChange={(e) => setBirthday(e.target.value)}
                                    className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required
                                />
                                {errors.birthday && (
                                    <span className="text-red-500 text-sm">{errors.birthday[0]}</span>
                                )}
                            </div>
                        )}

                        {/* Submit Button */}
                        <div className="flex items-center justify-between">
                            <button
                                type="submit"
                                className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            >
                                Create Counter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
