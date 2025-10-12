import Layout from '../components/layout.jsx';
import LayoutLeft from '../components/layout-left.jsx';
import LayoutRight from '../components/layout-right.jsx';
import StatCard from '../components/stat-card.jsx';
import GettingStarted from '../components/getting-started.jsx';

const Dashboard = ({ data }) => {
    const collectionsCount = data?.collections?.length || 0;

    // Calculate total routes count
    const totalRoutes = data?.routes
        ? Object.values(data.routes).reduce((total, endpoints) => total + endpoints.length, 0)
        : 0;

    return (
        <Layout>
            <LayoutLeft>
                <GettingStarted/>
            </LayoutLeft>
            <LayoutRight>
                <div className="space-y-6">
                    <StatCard value={collectionsCount} label="Registered Collections" />
                    <StatCard value={totalRoutes} label="Total Routes" />
                </div>
            </LayoutRight>
        </Layout>
    );
};

export default Dashboard;
