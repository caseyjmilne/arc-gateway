import RoutesCollection from './routes-collection.jsx';
import RoutesEmpty from './routes-empty.jsx';

const RoutesList = ({ routes }) => {
    if (!routes || Object.keys(routes).length === 0) {
        return <RoutesEmpty />;
    }

    return (
        <div className="space-y-6">
            {Object.entries(routes).map(([collectionName, endpoints]) => (
                <RoutesCollection
                    key={collectionName}
                    collectionName={collectionName}
                    endpoints={endpoints}
                />
            ))}
        </div>
    );
};

export default RoutesList;
