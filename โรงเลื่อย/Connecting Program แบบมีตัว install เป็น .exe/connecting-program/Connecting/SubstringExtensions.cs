namespace Connecting
{
    static class SubstringExtensions
    {
        public static string After(this string value, string after)
        {
            int stringindex = value.LastIndexOf(after);
            if (stringindex == -1) return "";
            int adjustSTRIndex = stringindex + after.Length;
            if (adjustSTRIndex >= value.Length) return "";
            return value.Substring(adjustSTRIndex);
        }

        public static string Between(this string value, string after, string before)
        {
            int posA = value.IndexOf(after);
            int posB = value.LastIndexOf(before);
            if (posA == -1) return "";
            if (posB == -1) return "";
            int adjustedPosA = posA + after.Length;
            if (adjustedPosA >= posB) return "";
            return value.Substring(adjustedPosA, posB - adjustedPosA);
        }
    }
}