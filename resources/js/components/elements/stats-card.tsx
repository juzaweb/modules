export interface StatsCardProps {
    title: string;
    data: string;
    className: string;
    icon?: string;
}

export default function StatsCard({title, data, className, icon}: StatsCardProps) {
    return (
        <div className={className}>
            <div className="card-body">
                <div className="d-flex flex-wrap align-items-center">
                    <i className={`fa ${icon || 'fa-list'} font-size-50 mr-3`}></i>
                    <div>
                        <div className="font-size-21 font-weight-bold">{title}</div>
                        <div className="font-size-15">{data}</div>
                    </div>
                </div>
            </div>
        </div>
    );
}