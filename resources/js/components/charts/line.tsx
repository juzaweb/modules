import { Line as LineChart } from 'react-chartjs-2';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
} from 'chart.js';

export interface LineProps {
    title: string;
    labels: string[];
    data: {
        labels: string[];
        datasets: {
            label: string;
            data: number[];
        }
    };
}

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
);

export default function Line({ title, labels, data }: LineProps) {
    const options = {
        plugins: {
            legend: {
                position: 'top' as const,
            },
            title: {
                display: true,
                text: title,
            },
        }
    }

    return (
        <div style={{width: undefined, height: '450px'}}>
            <LineChart
                datasetIdKey='id'
                options={options}
                data={{
                    labels: labels,
                    datasets: [
                        {
                            id: 1,
                            label: 'Dataset 1',
                            data: [5, 6, 7],
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        },
                        {
                            id: 2,
                            label: 'Dataset 2',
                            data: [3, 2, 1],
                            borderColor: 'rgb(53, 162, 235)',
                            backgroundColor: 'rgba(53, 162, 235, 0.5)',
                        },
                    ],
                }}
            />
        </div>
    );
}
