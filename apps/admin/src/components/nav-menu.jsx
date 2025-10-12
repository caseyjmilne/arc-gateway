import { NavLink } from 'react-router-dom';
import { __ } from '@wordpress/i18n';

const NavMenu = () => {
    return (
        <nav className="flex gap-6">
            <NavLink
                to="/"
                className={({ isActive }) =>
                    `text-sm font-medium transition-colors ${
                        isActive
                            ? 'text-sky-400'
                            : 'text-gray-400 hover:text-gray-300'
                    }`
                }
                end
            >
                {__('Dashboard', 'arc-gateway')}
            </NavLink>
            <NavLink
                to="/collections"
                className={({ isActive }) =>
                    `text-sm font-medium transition-colors ${
                        isActive
                            ? 'text-sky-400'
                            : 'text-gray-400 hover:text-gray-300'
                    }`
                }
            >
                {__('Collections', 'arc-gateway')}
            </NavLink>
        </nav>
    );
};

export default NavMenu;
