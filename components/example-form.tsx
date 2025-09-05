"use client";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { exampleSchema, ExampleInput } from "@/lib/validators";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Form, FormField, FormItem, FormLabel, FormMessage } from "@/components/ui/form";

export default function ExampleForm() {
    const form = useForm<ExampleInput>({ resolver: zodResolver(exampleSchema), defaultValues:{name:"", email:""} });
    const onSubmit = (values: ExampleInput) => console.log(values);
    return (
        <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
                <FormField control={form.control} name="name" render={({ field }) => (
                    <FormItem>
                        <FormLabel>Name</FormLabel>
                        <Input {...field} />
                        <FormMessage />
                    </FormItem>
                )}/>
                <FormField control={form.control} name="email" render={({ field }) => (
                    <FormItem>
                        <FormLabel>Email</FormLabel>
                        <Input type="email" {...field} />
                        <FormMessage />
                    </FormItem>
                )}/>
                <Button type="submit">Save</Button>
            </form>
        </Form>
    );
}