export const Layout = ({ children }) => {
  return (
    <div className="flex flex-col md:flex-row gap-6 w-full">
      {children}
    </div>
  );
};

export default Layout;
