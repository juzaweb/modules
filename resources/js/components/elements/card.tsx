export default function Card({config}: {config: { title: string, data: string, class: string }}) {
    return (
        <div className={config.class}>
            <div className="card-body">
                <div className="d-flex flex-wrap align-items-center">
                    <i className="fa fa-list font-size-50 mr-3"></i>
                    <div>
                        <div className="font-size-21 font-weight-bold">{config.title}</div>
                        <div className="font-size-15">{config.data}</div>
                    </div>
                </div>
            </div>
        </div>
    );
}