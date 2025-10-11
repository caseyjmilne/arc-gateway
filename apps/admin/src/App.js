import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import axios from 'axios';
import AppHeader from './components/app-header.js';
import CollectionsHeading from './components/collections-heading.js';
import RoutesHeading from './components/routes-heading.js';
import Layout from './components/Layout.jsx';
import LayoutLeft from './components/LayoutLeft.jsx';
import LayoutRight from './components/LayoutRight.jsx';
import CollectionsList from './components/CollectionsList.jsx';

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
            <div className="flex items-center justify-center min-h-[400px] bg-zinc-950 text-gray-400">
                <div>{__('Loading...', 'arc-gateway')}</div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="bg-zinc-950 min-h-screen -ml-[22px] p-8">
                <div className="bg-red-900/20 border border-red-800 rounded-lg p-4">
                    <p className="text-red-400">{__('Error loading data:', 'arc-gateway')} {error}</p>
                </div>
            </div>
        );
    }

    return (
        <div className="-ml-[22px] -mt-[10px] -mr-[20px] w-[calc(100%+40px)] bg-zinc-800 min-h-screen text-gray-400">
            <AppHeader />

            {/* Main Content */}
            <div className="px-8 py-6">
                <Layout>
                    <LayoutLeft>
                        {/* Collections Section */}
                        <div className="mb-8">
                            <CollectionsHeading />
                            <CollectionsList collections={data?.collections} />
                        </div>

                {/* Routes Section */}
                <div>
                    <RoutesHeading />
                    {data?.routes && Object.keys(data.routes).length > 0 ? (
                        <div className="space-y-6">
                            {Object.entries(data.routes).map(([collectionName, endpoints]) => (
                                <div key={collectionName} className="bg-zinc-900 rounded-lg overflow-hidden border border-zinc-800">
                                    <div className="bg-zinc-800 px-6 py-3 border-b border-zinc-700">
                                        <h3 className="!text-base !font-medium !text-gray-300 !leading-6 !m-0">
                                            {collectionName}
                                        </h3>
                                    </div>
                                    <ul className="divide-y divide-zinc-800">
                                        {endpoints.map((route, index) => (
                                            <li key={index} className="px-6 py-4 hover:bg-zinc-800 transition-colors">
                                                <div className="flex items-start">
                                                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-sky-800 text-sky-200 mr-3">
                                                        {route.method}
                                                    </span>
                                                    <div className="flex-1">
                                                        <div className="font-medium text-gray-300 mb-1">
                                                            {route.type}
                                                        </div>
                                                        <code className="text-sm text-gray-400 bg-zinc-800 px-2 py-1 rounded">
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
                        <div className="bg-zinc-900 rounded-lg p-6 text-center border border-zinc-800">
                            <p className="text-gray-500">{__('No routes registered.', 'arc-gateway')}</p>
                        </div>
                    )}
                </div>
                    </LayoutLeft>
                    <LayoutRight>
                        {/* Sidebar content will go here */}
                    </LayoutRight>
                </Layout>
            </div>
        </div>
    );
};

export default App;
