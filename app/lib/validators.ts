import { z } from "zod";
export const exampleSchema = z.object({
    name: z.string().min(2),
    email: z.string().email(),
});
export type ExampleInput = z.infer<typeof exampleSchema>;