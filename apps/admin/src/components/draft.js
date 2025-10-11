// Layout.jsx
export const Layout = ({ children }) => {
  return (
    <div className="flex flex-col md:flex-row gap-6 w-full">
      {children}
    </div>
  );
};

export const LayoutLeft = ({ children }) => {
  return (
    <div className="w-full md:w-2/3">
      {children}
    </div>
  );
};

export const LayoutRight = ({ children }) => {
  return (
    <div className="w-full md:w-1/3">
      {children}
    </div>
  );
};

// Usage:
import { Layout, LayoutLeft, LayoutRight } from './components/Layout';

function App() {
  return (
    <Layout>
      <LayoutLeft>
        <h1>Main Content</h1>
      </LayoutLeft>
      <LayoutRight>
        <h2>Sidebar</h2>
      </LayoutRight>
    </Layout>
  );
}