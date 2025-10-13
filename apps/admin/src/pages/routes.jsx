import RoutesHeading from '../components/routes-heading.js';
import RoutesList from '../components/routes-list.jsx';

const Routes = ({ data }) => {
    return (
        <div className="max-w-4xl">
            <RoutesHeading />
            <RoutesList routes={data?.routes} />
        </div>
    );
};

export default Routes;
