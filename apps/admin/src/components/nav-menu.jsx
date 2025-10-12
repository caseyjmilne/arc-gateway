import { NavLink } from 'react-router-dom';
import { __ } from '@wordpress/i18n';

const NavMenu = () => {
    return (
        <nav className="flex gap-6 ml-8">
            <NavLink
                to="/"
                className="text-sm font-medium text-gray-300"
                end
            >
                {__('Dashboard', 'arc-gateway')}
            </NavLink>
            <NavLink
                to="/collections"
                className="text-sm font-medium text-gray-300"
            >
                {__('Collections', 'arc-gateway')}
            </NavLink>
        </nav>
    );
};

export default NavMenu;
