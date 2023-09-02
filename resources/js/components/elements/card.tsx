import ElementBuilderChildren from "../ElementBuilderChildren";

export interface CardProps {
    title?: string;
    className: string;
    children?: Array<any>
    headerClassName?: string
    titleClassName?: string
}

export default function Card({title, className, children, headerClassName, titleClassName}: CardProps) {
    return (
        <div className={className}>
            {title ? (
                <div className={'card-header'+ (headerClassName ? ' ' + headerClassName : '')}>
                    <h3 className={`card-title`+ (titleClassName ? ' ' + titleClassName : '')}>{title}</h3>
                </div>
            ) : ''}

            <div className="card-body">
                <ElementBuilderChildren children={children} />
            </div>
        </div>
    );
}