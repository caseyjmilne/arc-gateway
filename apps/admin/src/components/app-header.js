import NavMenu from './nav-menu.jsx';

export default function AppHeader() {
    return(
        <header className="bg-zinc-900 border-b border-zinc-800 px-8 py-4">
            <div className="flex items-center">
                <h1 className="font-playfair !text-lg !font-bold !text-gray-300 !m-0">
                    ARC\Gateway
                </h1>
                <NavMenu />
            </div>
        </header>
    )
}