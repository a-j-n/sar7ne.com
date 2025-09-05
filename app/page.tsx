import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";

export default function Home() {
    return (
        <main className="container mx-auto p-6">
            <Card className="max-w-2xl mx-auto">
                <CardHeader>
                    <CardTitle>Sar7ne — MVP</CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                    <p className="text-sm opacity-80">
                        جاهزين نركّب الموديولات حسب المتطلبات.
                    </p>
                    <div className="flex gap-2">
                        <Button>Primary</Button>
                        <Button variant="outline">Secondary</Button>
                    </div>
                </CardContent>
            </Card>
        </main>
    );
}