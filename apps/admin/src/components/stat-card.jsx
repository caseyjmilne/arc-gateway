const StatCard = ({ value, label }) => {
    return (
        <div className="bg-zinc-900 rounded-lg p-8 text-center border border-zinc-800">
            <div className="text-6xl font-bold text-gray-200 mb-3">
                {value}
            </div>
            <div className="text-sm text-gray-400 uppercase tracking-wide">
                {label}
            </div>
        </div>
    );
};

export default StatCard;
