import '../../css/ProfileSummary.css';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function ProfileSummary({ user }) {
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
