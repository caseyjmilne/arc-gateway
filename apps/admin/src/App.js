import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import axios from 'axios';

const App = () => {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/wp-json/arc-gateway/v1/admin-data', {
                headers: {
                    'X-WP-Nonce': window.arcGatewayAdmin?.nonce || ''
                }
            });
            setData(response.data);
            setError(null);
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    if (loading) {
        return (
            <div className="flex items-center justify-center min-h-[400px]">
                <div className="text-gray-600">{__('Loading...', 'arc-gateway')}</div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="bg-red-50 border border-red-200 rounded-lg p-4">
                <p className="text-red-800">{__('Error loading data:', 'arc-gateway')} {error}</p>
            </div>
        );
    }

    return (
        <div className="max-w-5xl mx-auto py-6 px-4">
            <div className="mb-8">
                <h1 className="text-2xl font-bold text-gray-900">
                    {__('ARC Gateway Admin', 'arc-gateway')}
                </h1>
                <p className="mt-1 text-sm text-gray-600">
                    {__('Manage your collections and routes', 'arc-gateway')}
                </p>
            </div>

            {/* Collections Section */}
            <div className="mb-8">
                <h2 className="text-xl font-semibold text-gray-900 mb-4">
                    {__('Registered Collections', 'arc-gateway')}
                </h2>
                {data?.collections && data.collections.length > 0 ? (
                    <div className="bg-white shadow rounded-lg overflow-hidden">
                        <ul className="divide-y divide-gray-200">
                            {data.collections.map((collection, index) => (
                                <li key={index} className="px-6 py-4 hover:bg-gray-50">
                                    <div className="flex items-center justify-between">
                                        <div>
                                            <span className="font-medium text-gray-900">
                                                {collection.alias}
                                            </span>
                                            <span className="ml-3 text-sm text-gray-500">
                                                ({collection.class})
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            ))}
                        </ul>
                    </div>
                ) : (
                    <div className="bg-gray-50 rounded-lg p-6 text-center">
                        <p className="text-gray-600">{__('No collections registered.', 'arc-gateway')}</p>
                    </div>
                )}
            </div>

            {/* Routes Section */}
            <div>
                <h2 className="text-xl font-semibold text-gray-900 mb-4">
                    {__('Registered Routes', 'arc-gateway')}
                </h2>
                {data?.routes && Object.keys(data.routes).length > 0 ? (
                    <div className="space-y-6">
                        {Object.entries(data.routes).map(([collectionName, endpoints]) => (
                            <div key={collectionName} className="bg-white shadow rounded-lg overflow-hidden">
                                <div className="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h3 className="text-lg font-medium text-gray-900">
                                        {collectionName}
                                    </h3>
                                </div>
                                <ul className="divide-y divide-gray-200">
                                    {endpoints.map((route, index) => (
                                        <li key={index} className="px-6 py-4 hover:bg-gray-50">
                                            <div className="flex items-start">
                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800 mr-3">
                                                    {route.method}
                                                </span>
                                                <div className="flex-1">
                                                    <div className="font-medium text-gray-900 mb-1">
                                                        {route.type}
                                                    </div>
                                                    <code className="text-sm text-gray-600 bg-gray-50 px-2 py-1 rounded">
                                                        {route.route}
                                                    </code>
                                                </div>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="bg-gray-50 rounded-lg p-6 text-center">
                        <p className="text-gray-600">{__('No routes registered.', 'arc-gateway')}</p>
                    </div>
                )}
            </div>
        </div>
    );
};

export default App;
