const RouteItem = ({ route }) => {
    return (
        <li className="px-6 py-4 hover:bg-zinc-800 transition-colors">
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
    );
};

export default RouteItem;
