export interface CardProps {
    title: string;
    data: string;
    className: string;
    icon?: string;
}

export default function Card({title, data, className, icon}: CardProps) {
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