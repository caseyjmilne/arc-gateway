import RouteItem from './route-item.jsx';

const RoutesCollection = ({ collectionName, endpoints }) => {
    return (
        <div className="bg-zinc-900 rounded-lg overflow-hidden border border-zinc-800">
            <div className="bg-zinc-800 px-6 py-3 border-b border-zinc-700">
                <h3 className="!text-base !font-medium !text-gray-300 !leading-6 !m-0">
                    {collectionName}
                </h3>
            </div>
            <ul>
                {endpoints.map((route, index) => (
                    <RouteItem key={index} route={route} />
                ))}
            </ul>
        </div>
    );
};

export default RoutesCollection;
