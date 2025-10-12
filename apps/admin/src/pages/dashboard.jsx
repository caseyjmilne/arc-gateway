import RoutesHeading from '../components/routes-heading.js';
import CollectionsHeading from '../components/collections-heading.js';
import Layout from '../components/layout.jsx';
import LayoutLeft from '../components/layout-left.jsx';
import LayoutRight from '../components/layout-right.jsx';
import CollectionsList from '../components/collections-list.jsx';
import RoutesList from '../components/routes-list.jsx';

const Dashboard = ({ data }) => {
    return (
        <Layout>
            <LayoutLeft>
                <RoutesHeading />
                <RoutesList routes={data?.routes} />
            </LayoutLeft>
            <LayoutRight>
                <CollectionsHeading />
                <CollectionsList collections={data?.collections} />
            </LayoutRight>
        </Layout>
    );
};

export default Dashboard;
