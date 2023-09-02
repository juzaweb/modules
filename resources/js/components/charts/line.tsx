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
import {useEffect, useState} from "react";
import axios from "axios";

export interface LineProps {
    title?: string;
    labels: string[];
    data?: Array<{
        label: string;
        data: number[];
    }>;
    dataUrl?: string
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

export default function Line({ title, labels, data, dataUrl }: LineProps) {
    const [datasets, setDatasets] = useState<Array<any>>(data || []);

    useEffect(() => {
        if (dataUrl) {
            axios.get(dataUrl).then((response) => {
                setDatasets(response.data);
            });
        }
    }, []);

    const options = {
        plugins: {
            legend: {
                position: 'top' as const,
            },
            title: {
                display: !!title,
                text: title,
            },
        }
    }

    return (
        <LineChart
            height={80}
            options={options}
            data={{
                labels: labels,
                datasets: datasets,
            }}
        />
    );
}
