import { useState } from '@wordpress/element';

const RouteItem = ({ route }) => {
    const [isOpen, setIsOpen] = useState(false);

    return (
        <li className="border-b border-zinc-800 last:border-b-0 hover:bg-zinc-800 transition-colors !mb-0">
            <button
                onClick={() => setIsOpen(!isOpen)}
                className="w-full px-6 py-4 text-left flex items-center gap-3"
            >
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-sky-800 text-sky-200">
                    {route.method}
                </span>
                <span className="text-sm text-gray-300 flex-1 font-mono">
                    {route.route}
                </span>
                <svg
                    className={`w-5 h-5 text-gray-400 transition-transform flex-shrink-0 ${isOpen ? 'rotate-180' : ''}`}
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            {isOpen && (
                <div className="px-6 pb-4 pt-2 bg-zinc-800/50">
                    <div className="space-y-2">
                        <div>
                            <span className="text-xs text-gray-500 uppercase tracking-wide">Type</span>
                            <div className="text-sm text-gray-300 mt-1">{route.type}</div>
                        </div>
                        <div>
                            <span className="text-xs text-gray-500 uppercase tracking-wide">Description</span>
                            <div className="text-sm text-gray-300 mt-1">{route.description || 'No description available'}</div>
                        </div>
                    </div>
                </div>
            )}
        </li>
    );
};

export default RouteItem;
