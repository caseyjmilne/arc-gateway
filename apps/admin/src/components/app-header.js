import NavMenu from './nav-menu.jsx';

export default function AppHeader() {
    return(
        <header className="bg-zinc-300 px-3 py-1">
            <div className="flex items-center">
                <h1 className="font-playfair !text-lg !font-black !text-gray-800 !m-0 !p-0">
                    ARC\Gateway
                </h1>
                <NavMenu />
            </div>
        </header>
    )
}