import '../../css/ProfileSummary.css';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function ProfileSummary({ user, counters  }) {
    console.log('===============================')
    console.log(counters)

    return (
        <AuthenticatedLayout>
            <Head title="Profile" />

            <div className="profile-container">
                <div className="profile-wrapper">
                    <div className="profile-card">
                        <div className="profile-card-content">
                            {/* Profile Information */}
                            <h3 className="profile-header">Profile Information</h3>
                            <div className="profile-field">
                                <p>
                                    <span className="profile-label">Name:</span>
                                    <span className="profile-value"> {user.name}</span>
                                </p>
                                <p className="profile-field">
                                    <span className="profile-label">Counters:</span>
                                </p>
                                <ul className="space-y-4">
                                    {counters.map((counter) => (
                                        <li key={counter.id}
                                            className="flex items-center justify-between p-4 bg-white shadow-md rounded-lg hover:bg-gray-100 transition duration-300">
                                            {/* Counter details */}
                                            <div className="flex-1">
                                                <span
                                                    className="text-lg font-semibold text-gray-800">{counter.name} </span>
                                                <span className="text-sm text-gray-600">{counter.type}</span>
                                            </div>

                                            {/* Kid details */}
                                            {counter.type === 'kid' && !counter.pregnancy &&(
                                            <div className="flex items-center space-x-4">
                                                <span className="text-sm text-gray-600">Name: {counter.kid.name}</span>
                                                <span className="text-sm text-gray-500">Gender: {counter.kid.gender}</span>
                                                <span className="text-sm text-gray-500">Age: {counter.kid.age}</span>
                                            </div>
                                            )}
                                            {/* Pregnancy details */}
                                            {counter.pregnancy && (
                                                <div className="flex items-center space-x-4">
                                                    <span
                                                        className="text-sm text-gray-500">Days left: {counter.pregnancy.days_left}</span>

                                                    <span
                                                        className="text-sm text-gray-700">babies: {counter.pregnancy.fetuses.length}</span>
                                                    <span className="text-sm text-gray-500">
                                                    Genders: {counter.pregnancy.fetuses.map(fetus => fetus.gender).join(', ')}
                                                    </span>
                                                </div>
                                            )}
                                        </li>

                                    ))}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
